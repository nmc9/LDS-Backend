# run reports

phpmetrics --report-html=reports ./app --junit=junit.xml


# Run phpunit 

vendor/bin/phpunit --log-junit=junit.xml

vendor/bin/phpunit --coverage-html phpunitreports