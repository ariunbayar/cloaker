1. Install composer::

    cd lib
    php -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"
    cd ..

2. Install required packages (see composer.json)::

    lib/composer.phar install -d lib/

3. New migrations run::

    php run_migration.php

Deploy
===
Run following command to deploy::

    lib/deploy.sh

Run migration script::

    lib/php migration_script.php

