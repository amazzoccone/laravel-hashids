#!/usr/bin/env groovy
pipeline {
    agent any

    stages {
        stage('Install') {
            steps {
                checkout scm
                sh returnStdout: true, script: 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }

        stage('Test') {
            steps {
                sh returnStdout: true, script: "./vendor/bin/phpunit --debug -c phpunit.xml --debug --log-junit logs/junit.xml"
            }
            post {
                always {
                    junit 'logs/junit.xml'
                }
            }
        }
    }
    post {
        failure {
            slackSend (color: '#4CAF50', channel: '#alerts', message: "Package Build failed: ${env.JOB_NAME} (<${env.BUILD_URL}|build ${env.BUILD_NUMBER}>)")
        }
    }
}
