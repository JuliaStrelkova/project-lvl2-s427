install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR2 src bin

cs-fix:
	composer run-script phpcbf -- --standard=PSR2 src bin

