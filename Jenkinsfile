#!groovy

pipeline {
    agent any
    options {
            timeout(time: 1, unit: 'HOURS')
    }
    scm {
        git {
            remote {
                github('redCOMPONENT-COM/redSHOP.git')
                refspec('+refs/pull/*:refs/remotes/origin/pr/*')
            }
            branch('${sha1}')
        }
    }

    triggers {
        pullRequest {
            admins(['jooservices', 'puneet0191', 'thongredweb', 'ducredweb', 'nhungredweb', 'turedweb', 'luredweb'])
            cron('* * * * *')
            triggerPhrase('OK to test')
            useGitHubHooks()
            permitAll()
            extensions {
                commitStatus {
                    completedStatus('SUCCESS', 'All is well')
                    completedStatus('FAILURE', 'Somethig went wrong. Investigate!')
                    completedStatus('PENDING', 'still in progress...')
                    completedStatus('ERROR', 'Something went really wrong. Investigate!')
                }
            }
        }
    }

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
            environment {
                CLOUD_NAME= 'redcomponent'
                API_KEY= '365447364384436'
                API_SECRET='Q94UM5kjZkZIrau8MIL93m0dN6U'
                GITHUB_TOKEN='4d92f9e8be0eddc0e54445ff45bf1ca5a846b609'
                ORGANIZATION='redCOMPONENT-COM'
                REPO='redSHOP'
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
