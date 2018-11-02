<?php

namespace Themosis\Taxonomy;

use Themosis\Hook\IHook;
use Themosis\Taxonomy\Contracts\TaxonomyInterface;

class Taxonomy implements TaxonomyInterface
{
    /**
     * @var string
     */
    protected $slug;

    /**
     * @var array
     */
    protected $objects;

    /**
     * @var array
     */
    protected $args;

    /**
     * @var IHook
     */
    protected $action;

    public function __construct(string $slug, array $objects, IHook $action)
    {
        $this->slug = $slug;
        $this->objects = $objects;
        $this->action = $action;
    }

    /**
     * Set taxonomy labels.
     *
     * @param array $labels
     *
     * @return TaxonomyInterface
     */
    public function setLabels(array $labels): TaxonomyInterface
    {
        if (isset($this->args['labels'])) {
            $this->args['labels'] = array_merge($this->args['labels'], $labels);
        } else {
            $this->args['labels'] = $labels;
        }

        return $this;
    }

    /**
     * Return taxonomy labels.
     *
     * @return array
     */
    public function getLabels(): array
    {
        return $this->args['labels'] ?? [];
    }

    /**
     * Return a taxonomy label by name.
     *
     * @param string $name
     *
     * @return string
     */
    public function getLabel(string $name): string
    {
        $labels = $this->getLabels();

        return $labels[$name] ?? '';
    }

    /**
     * Set taxonomy arguments.
     *
     * @param array $sargs
     *
     * @return TaxonomyInterface
     */
    public function setArguments(array $sargs): TaxonomyInterface
    {
        $this->args = array_merge($this->args, $sargs);

        return $this;
    }

    /**
     * Return taxonomy arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->args;
    }

    /**
     * Return a taxonomy argument.
     *
     * @param string $property
     *
     * @return mixed
     */
    public function getArgument(string $property)
    {
        $args = $this->getArguments();

        return $args[$property] ?? null;
    }

    /**
     * Register the taxonomy.
     *
     * @return TaxonomyInterface
     */
    public function set(): TaxonomyInterface
    {
        if (function_exists('current_filter') && 'init' === $hook = current_filter()) {
            $this->register();
        } else {
            $this->action->add('init', [$this, 'register']);
        }

        return $this;
    }

    /**
     * Register taxonomy hook callback.
     */
    public function register()
    {
        register_taxonomy($this->slug, $this->objects, $this->getArguments());
    }

    /**
     * Set taxonomy objects.
     *
     * @param array|string $objects
     *
     * @return TaxonomyInterface
     */
    public function setObjects($objects): TaxonomyInterface
    {
        $this->objects = array_merge($this->objects, (array) $objects);

        return $this;
    }

    /**
     * Return taxonomy attached objects.
     *
     * @return array
     */
    public function getObjects(): array
    {
        return $this->objects;
    }
}