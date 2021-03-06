<?php
/**
* Remote Post Swap plugin autoloader.
*
* Autoload classes with namespaces.
*
* Add the namespaces via the rps_add_namespace() method
* then classes can be loaded by using new RPS\Class_Name_Here;
*
* @author 	Tyler Bailey
* @version 0.8.0
* @package remote-post-swap
* @subpackage remote-post-swap/inc
*/

namespace RPS;

if(!class_exists('RPS_Autoloader')) :

    class RPS_Autoloader {
        /**
         * An associative array where the key is a namespace prefix and the value
         * is an array of base directories for classes in that namespace.
         *
         * @var array
         * @since 0.7.0
         */
        protected $prefixes = array();
        /**
         * Register loader with SPL autoloader stack.
         *
         * @return void
         * @since 0.7.0
         */
        public function rps_register() {
            spl_autoload_register(array($this, 'rps_load_class'));
        }

        /**
         * Adds a base directory for a namespace prefix.
         *
         * @param string $prefix The namespace prefix.
         * @param string $base_dir A base directory for class files in the
         * namespace.
         * @param bool $prepend If true, prepend the base directory to the stack
         * instead of appending it; this causes it to be searched first rather
         * than last.
         * @return void
         * @since 0.7.0
         */
        public function rps_add_namespace($prefix, $base_dir, $prepend = false) {
            // normalize namespace prefix
            $prefix = trim($prefix, '\\') . '\\';
            // normalize the base directory with a trailing separator
            $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';
            // initialize the namespace prefix array
            if (isset($this->prefixes[$prefix]) === false) {
                $this->prefixes[$prefix] = array();
            }
            // retain the base directory for the namespace prefix
            if ($prepend) {
                array_unshift($this->prefixes[$prefix], $base_dir);
            } else {
                array_push($this->prefixes[$prefix], $base_dir);
            }
        }

        /**
         * Loads the class file for a given class name.
         *
         * @param string $class The fully-qualified class name.
         * @return mixed The mapped file name on success, or boolean false on
         * failure.
         * @since 0.7.0
         */
        public function rps_load_class($class) {
            // the current namespace prefix
            $prefix = $class;
            // work backwards through the namespace names of the fully-qualified
            // class name to find a mapped file name
            while (false !== $pos = strrpos($prefix, '\\')) {
                // retain the trailing namespace separator in the prefix
                $prefix = substr($class, 0, $pos + 1);
                // the rest is the relative class name
                $relative_class = substr($class, $pos + 1);
                // try to load a mapped file for the prefix and relative class
                $mapped_file = $this->rps_load_file($prefix, $relative_class);
                if ($mapped_file) {
                    return $mapped_file;
                }
                // remove the trailing namespace separator for the next iteration
                // of strrpos()
                $prefix = rtrim($prefix, '\\');
            }
            // never found a mapped file
            return false;
        }

        /**
         * Load the mapped file for a namespace prefix and relative class.
         *
         * @param string $prefix The namespace prefix.
         * @param string $relative_class The relative class name.
         * @return mixed Boolean false if no mapped file can be loaded, or the
         * name of the mapped file that was loaded.
         * @since 0.7.0
         */
        protected function rps_load_file($prefix, $relative_class) {
            // are there any base directories for this namespace prefix?
            if (isset($this->prefixes[$prefix]) === false) {
                return false;
            }

            // look through base directories for this namespace prefix
            foreach ($this->prefixes[$prefix] as $base_dir) {
                $file = $base_dir
                    . str_replace('\\', '/', 'class-' . strtolower(str_replace("_", "-", $relative_class)))
                    . '.php';

                // if the mapped file exists, require it
                if ($this->rps_require_file($file)) {
                    // yes, we're done
                    return $file;
                }
            }
            // never found it
            return false;
        }

        /**
         * If a file exists, require it from the file system.
         *
         * @param string $file The file to require.
         * @return bool True if the file exists, false if not.
         * @since 0.7.0
         */
        protected function rps_require_file($file) {
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        }
    }

endif;
