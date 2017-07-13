pipeline {
    agent none
    stages {
        stage('Example Build') {
            agent {
                docker {
                    image 'pensiero/apache-php-mysql'
                }
            }
            steps {
                sh 'php --version'
            }
        }
        stage('Example Test') {
            agent {
                docker {
                    image 'joomlaprojects/docker-systemtests'
                    args  '--user 0'
                }
            }
            steps {
                echo 'Hello, JDK'
                sh 'java -version'
            }
        }
    }
}
