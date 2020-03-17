# Telegram API retranslator for PHP 7.2

This sample demonstrates how to deploy a *very* basic application to Google
App Engine for PHP 7.2.

View the [full tutorial](https://cloud.google.com/appengine/docs/standard/php7/quickstart)

Install to GoogleCloud APP Engine:

# for view existing files
	ls a
# if already exists
	rm -fR TelegramAPIRetranslator
git clone https://github.com/pykki/TelegramAPIRetranslator
cd TelegramAPIRetranslator
# add your WEBHOOK_URL and CLIENT_KEY to index.php
# for testing run
	php -S localhost:8080
# for first install
	gcloud app create
gcloud app deploy
