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
                    echo ${JOB_NAME}
                    echo 'next'
                    echo ${sha1}
                    echo 'next'
                    echo ${PR_NUMBER}
                    echo 'next'
                    echo ${BUILD_NUMBER}
                    echo 'next'
                    echo ${CHANGE_ID}
                    echo 'next'
                    echo ${BRANCH_NAME}
                    echo 'next'
                '''
            }
            post {
                always {
                    step([$class: 'WsCleanup'])
                }
            }
        }
    }
}
