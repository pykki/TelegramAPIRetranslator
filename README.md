# Telegram API retranslator for PHP 7.2

This sample demonstrates how to deploy a *very* basic application to Google
App Engine for PHP 7.2.

View the [full tutorial](https://cloud.google.com/appengine/docs/standard/php7/quickstart)

#Install to GoogleCloud APP Engine:

For view existing files
	ls a
If already exists
	rm -fR TelegramAPIRetranslator
	
	git clone https://github.com/pykki/TelegramAPIRetranslator
	cd TelegramAPIRetranslator
Add your WEBHOOK_URL and CLIENT_KEY to index.php
For testing run
	php -S localhost:8080
For first install
	gcloud app create

	gcloud app deploy
