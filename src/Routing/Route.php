<?php

namespace Oreto\Willow\Routing;

class Route {
    public bool $ajax = false;
    public string $handler;
    public int $ttl = 0;
    public int $kbps = 0;

    public function __construct(
        private string $method
        , private string $name
        , private string $pattern
    ) {}


    public function getMethod(): string {
        return $this->method;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getPattern(): string {
        return $this->pattern;
    }

    /**
     * Set the string reference to the class function handler used for the controller route
     * example: Oreto\Willow\App->index
     * @param string $class  Class name such as App::class
     * @param string $action Name of the controller function
     * @return Route         Reference to this route
     */
    function handler(string $class, string $action): Route {
        $this->handler = $class."->$action";
        return $this;
    }
    /**
     * Same as handler, builds the handler using :: static syntax.
     */
    function staticHandler(string $class, string $action): Route {
        $this->handler = $class."::$action";
        return $this;
    }

    public function ttl(int $seconds): Route {
        $this->ttl = $seconds;
        return $this;
    }
    public function kbps(int $kbps): Route {
        $this->kbps = $kbps;
        return $this;
    }

    public function ajax(): Route {
        $this->ajax = true;
        return $this;
    }
}