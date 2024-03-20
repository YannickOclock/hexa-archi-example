<?php

namespace App\Utils;

use Exception;

class Dispatcher
{
    /**
     * @var string[]
     */
    private mixed $params = [];

    /**
     * @var string
     */
    private string $controller;

    /**
     * @var string
     */
    private string $controllersNamespace;

    /**
     * @var mixed
     */
    private mixed $controllersArguments;

    /**
     * @var string
     */
    private string $method;

    /**
     * Constructor method
     *
     * @param mixed $match Array returned by AltoRouter::match()
     * @param array|string $fourOFourAction A valid target for the 404 action
     * @throws Exception
     */
    public function __construct(mixed $match, array|string $fourOFourAction)
    {
        // if no route were matched, trigger the 404 action by parsing it so it is called by a later dispatch()
        if (!$match) {
            header('HTTP/1.0 404 Not Found');
            $this->parseTarget($fourOFourAction);
            return;
        }

        // Getting DISPATCH infos provided by AltoRouter
        $this->parseTarget($match['target']);

        // Getting URL params (dynamic parts in routes' URL pattern)
        $this->params = $match['params'];
    }

    /**
     * Parses the target into valid controller and method properties
     *
     * @param array|string $target
     * @return void
     * @throws Exception
     */
    public function parseTarget(array|string $target): void
    {
        // Getting controller's name and method's name
        // if it's an array
        if (is_array($target)) {
            if (!empty($target[0]) && !empty($target[1])) {
                $this->controller = $target[0];
                $this->method = $target[1];
            } else {
                throw new Exception('Target (array) of current route is incorrect');
            }
        } elseif (is_string($target)) {
            // if it's a string containing controller and method
            // Controller#method or Controller::method or Controller@method
            $availableSeparators = ['#', '::', '@'];
            $separatorFound = false;

            foreach ($availableSeparators as $currentSeparator) {
                if (str_contains($target, $currentSeparator)) {
                    $separatorFound = true;
                    $explodedInfos = explode($currentSeparator, $target);
                    $this->controller = $explodedInfos[0];
                    $this->method = $explodedInfos[1];

                    break;
                }
            }

            if (!$separatorFound) {
                throw new Exception('Target (string) of current route is incorrect');
            }
        } else {
            throw new Exception('Target of current route has incorrect type');
        }
    }

    /**
     * Dispatches matched route
     *
     * @return array
     * @throws Exception
     */
    public function dispatch(): array
    {
        if (!empty($this->controller) && !empty($this->method)) {
            // get Controller FQCN
            $controllerName = $this->controller;
            // If namespace is defined
            if (!empty($this->controllersNamespace)) {
                // If controller does not contain namespace
                if (!str_contains($this->controller, $this->controllersNamespace)) {
                    // then, add its namespace
                    $controllerName = str_replace('\\\\', '\\', $this->controllersNamespace . '\\' . $this->controller);
                }
            }

            $controllerArguments = [];
            // controller instanciation
            // if an argument to this constructor is set
            if (!empty($this->controllersArguments)) {
                // If it's an array
                if (is_array($this->controllersArguments)) {
                    // Then, each element will be an argument
                    //$controller = new $controllerName(...array_values($this->controllersArguments));
                    $controllerArguments = [...array_values($this->controllersArguments)];
                } else {
                    // Else, we add only this argument
                    //$controller = new $controllerName($this->controllersArguments);
                    $controllerArguments = [$this->controllersArguments];
                }
            }
            // method call with arguments unpacking
            //$controller->{$this->method}(...array_values($this->params));
            return [
                1 => [
                    0 => $controllerName,
                    1 => $this->method,
                ],
                2 => $controllerArguments,
            ];
        } else {
            throw new Exception('Cannot dispatch : controller or method is empty');
        }
    }

    /**
     * Set the value of controllersNamespace property
     *
     * @param string $controllersNamespace
     */
    public function setControllersNamespace(string $controllersNamespace): void
    {
        $this->controllersNamespace = $controllersNamespace;
    }

    /**
     * Set the value of controllersArguments
     *
     * @param mixed $controllersArguments
     */
    public function setControllersArguments(...$controllersArguments): void
    {
        $this->controllersArguments = $controllersArguments;
    }
}
