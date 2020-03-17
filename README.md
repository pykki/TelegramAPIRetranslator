# Telegram API retranslator for PHP 7.2
(if host-provider blocked Telegram servers)

This sample demonstrates how to deploy a *very* basic application to Google
App Engine for PHP 7.2.

View the [full tutorial](https://cloud.google.com/appengine/docs/standard/php7/quickstart)

1. Put webhook.php in your site
2. Install to GoogleCloud APP Engine:


    ls a // for view existing files
    rm -fR TelegramAPIRetranslator // if already exists
    git clone https://github.com/pykki/TelegramAPIRetranslator
    cd TelegramAPIRetranslator
    // Add your WEBHOOK_URL and CLIENT_KEY to index.php
    php -S localhost:8080 // for testing run
    gcloud app create // for first install
    gcloud app deploy


3. Set BOT_TOKEN, WEBHOOK_URL, RETRANSLATOR_URL, REMOTE_KEY (REMOTE_KEY==CLIENT_KEY) in webhook.php
4. Open https://[your_site]/webhook.php?setWebhook&url=https://[project_name].appspot.com/ (REMOTE_CONTROL must be TRUE)
