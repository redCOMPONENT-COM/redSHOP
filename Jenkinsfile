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
                    args  '--user 0 --privileged'
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
        stage('Browser Tests run') {
            steps {
                sh "vendor/bin/robo kill:selenium"
            }
            post
            {
                always {
                    sh "export CLOUD_NAME=redcomponent"
                    sh "export API_KEY=365447364384436"
                    sh "export API_SECRET=Q94UM5kjZkZIrau8MIL93m0dN6U"
                    sh "export GITHUB_TOKEN=4d92f9e8be0eddc0e54445ff45bf1ca5a846b609"
                    sh "export ORGANIZATION=redCOMPONENT-COM"
                    sh "export REPO=redSHOP"
                    sh "echo $ORGANIZATION"
                    sh "vendor/bin/robo send:screenshot-from-travis-to-github $CLOUD_NAME $API_KEY $API_SECRET $GITHUB_TOKEN $ORGANIZATION $REPO $ghprbPullId"
                }
            }
        }
    }
}
