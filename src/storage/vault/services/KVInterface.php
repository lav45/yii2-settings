<?php

namespace lav45\settings\storage\vault\services;

interface KVInterface
{
    /**
     * @param string $path
     * @param array $data
     * @return bool|array
     */
    public function post(string $path, array $data = []);

    /**
     * @param string $path
     * @return bool|array
     */
    public function get(string $path);

    /**
     * @param string $path
     * @return bool|array
     */
    public function delete(string $path);

    /**
     * @param string $path
     * @return array
     */
    public function list(string $path);
}