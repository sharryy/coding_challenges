# For Internal Purposes Only
# This script creates a new project with the given name and sets up the necessary files and directories.
# The dependencies might change in future
# Usage: ./create-new.sh <project-name> <description>

PROJECT_NAME=$1
DESCRIPTION=$2

# See if the project name exists already
if [ -d "$PROJECT_NAME" ]; then
  printf "\nProject already exists\n"
  exit 1
fi

# Create the project directory
mkdir $PROJECT_NAME

# Create the necessary directories
mkdir $PROJECT_NAME/src
mkdir $PROJECT_NAME/tests

touch $PROJECT_NAME/composer.json
touch $PROJECT_NAME/artisan

# Navigate to the project directory
cd $PROJECT_NAME

# Set up autoloading in composer.json
cat >composer.json <<EOF
{
  "name": "sharryy/$PROJECT_NAME",
  "description": "$DESCRIPTION",
  "type": "library",
  "version": "1.0.0",
  "require": {},
  "require-dev": {},
  "license": "MIT",
  "autoload": {
    "psr-4": {
      "App\\\\": "src/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Tests\\\\": "tests/"
    }
  },
  "authors": [
    {
      "name": "Sharryy",
      "email": "ibneadam388@gmail.com"
    }
  ]
}
EOF

# Validate the composer.json file
composer validate >/dev/null 2>&1

if [ $? -ne 0 ]; then
  printf "\nInvalid composer.json file\n"
  exit 1
fi

# Install the dependencies
composer require pestphp/pest --dev --with-all-dependencies
composer require symfony/var-dumper
composer require symfony/console

./vendor/bin/pest --init

# Set up the artisan file
cat >artisan <<EOF
#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

\$application = new Application();
\$application->run();

EOF

touch src/$PROJECT_NAME.php
cat >src/$PROJECT_NAME.php <<EOF
<?php

namespace App;

class Stub
{
    public function __construct()
    {
        //
    }
}

EOF
