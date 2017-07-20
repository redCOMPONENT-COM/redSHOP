#!groovy

pipeline {
    agent any

    stages {
        stage('Setup') {
            steps {
                gitPR = sh(returnStdout: true, script: 'env.BRANCH_NAME.replace(/^PR-/, '')').trim()
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
                    echo ${gitPR}
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
