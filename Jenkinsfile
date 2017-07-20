#!groovy

pipeline {
    agent any

    stages {
        stage('Setup') {
            steps {
                sh '''
                    pwd && \
                    whoami && \
                    echo $WORKSPACE && \
                    ls -la
                '''
            }
            post {
                always {
                    step([$class: 'WsCleanup'])
                }
            }
        }
        stage('Browser Tests setup') {
            agent {
                docker {
                    image 'joomlaprojects/docker-systemtests'
                    args  '--user 0 --privileged=true'
                }
            }
            steps {
                sh 'bash build/jenkins/system-tests.sh'
            }
            post {
                always {
                    step([$class: 'WsCleanup'])
                }
            }
        }
    }
}
