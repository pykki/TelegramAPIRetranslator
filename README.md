# Telegram API retranslator for PHP 7.2

This sample demonstrates how to deploy a *very* basic application to Google
App Engine for PHP 7.2.

View the [full tutorial](https://cloud.google.com/appengine/docs/standard/php7/quickstart)

###Install to GoogleCloud APP Engine:

    ls a // for view existing files
    rm -fR TelegramAPIRetranslator // if already exists
    git clone https://github.com/pykki/TelegramAPIRetranslator
    cd TelegramAPIRetranslator
    // Add your WEBHOOK_URL and CLIENT_KEY to index.php
    php -S localhost:8080 // for testing run
    gcloud app create // for first install
    gcloud app deploy
