<?php

defined('_JEXEC') or die;

class ManagementsRouter extends JComponentRouterBase{

    public function build(&$query)
    {

        $segments = array();

        if (isset($query['view']))
        {
            $segments[] = $query['view'];
            unset($query['view']);
        }

        if(isset($query['id']))
        {
            $segments[] = $query['id'];
            unset($query['id']);
        };

        return $segments;

    }

    public function parse(&$segments)
    {

        $vars = array();

        $vars['view'] = $segments[0];

        if(isset($segments[1])){
            $vars['id'] = $segments[1];
        }

        return $vars;

    }
}

function managementsBuildRoute(&$query)
{
    $router = new ManagementsRouter;

    return $router->build($query);
}

function managementsParseRoute($segments)
{
    $router = new ManagementsRouter;

    return $router->parse($segments);
}
