pipeline {
	agent any
	options {
		timeout(time: 2, unit: 'HOURS')
		buildDiscarder(logRotator(numToKeepStr: '4', daysToKeepStr: '10', artifactDaysToKeepStr: '15', artifactNumToKeepStr: '4'))
		disableConcurrentBuilds()
	}
	triggers {
		pollSCM '30 08 22 06 *'
	}
	stages {
		stage('Docker Setup') {
			steps {
				dockerNetworkCreate("tn-${BUILD_TAG}")
				dockerDBRun("db-${BUILD_TAG}", 'root', "tn-${BUILD_TAG}")
			}
		}
		stage ('Tests Setup') {
			environment {
				GITHUB_TOKEN='4d92f9e8be0eddc0e54445ff45bf1ca5a846b609'
				GITHUB_REPO='redCOMPONENT-COM/redshop'
				CLOUDINARY_CLOUD_NAME='redcomponent'
				CLOUDINARY_API_KEY='365447364384436'
				CLOUDINARY_API_SECRET='Q94UM5kjZkZIrau8MIL93m0dN6U'
				SLACK_WEBHOOK='https://hooks.slack.com/services/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'
				SLACK_CHANNEL='#redshop-builds'
				GITHUB_REPO_OWNER='redCOMPONENT-COM'
				REPO='redshop'
			}
			agent {
				docker {
					image 'jatitoam/docker-systemtests'
					args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
				}
			}
			steps {
				sh "./build/test-setup.sh"
				stash includes: 'vendor/**', name: 'vendor'
				stash includes: 'joomla-cms.zip', name: 'joomla-cms'
				stash includes: 'chromedriver_linux64.zip', name: 'chromeD'
				stash includes: 'redshop.zip', name: 'redshop'
				stash includes: 'joomla-cms-database.zip', name: 'database-dump'
			}
			post {
				always {
					cleanWs()
				}
			}
		}
		stage ('Deploy Package') {
			environment {
				GITHUB_TOKEN='4d92f9e8be0eddc0e54445ff45bf1ca5a846b609'
				GITHUB_REPO_OWNER='redCOMPONENT-COM'
				REPO='redshop'
			}
			steps {
				unstash 'redshop'
				sh 'ssh -p4975 installers@test.redcomponent.com "mkdir -p public_html/redshop/PR/$CHANGE_ID"'
				sh 'scp -P4975 redshop.zip installers@test.redcomponent.com:/home/installers/public_html/redshop/PR/$CHANGE_ID/redshop.zip'
			}
			post {
				always {
					cleanWs()
				}
			}

		}
		stage('Automated Tests - Batch 1/3') {
			environment {
				GITHUB_TOKEN='4d92f9e8be0eddc0e54445ff45bf1ca5a846b609'
				GITHUB_REPO='redCOMPONENT-COM/redshop'
				CLOUDINARY_CLOUD_NAME='redcomponent'
				CLOUDINARY_API_KEY='365447364384436'
				CLOUDINARY_API_SECRET='Q94UM5kjZkZIrau8MIL93m0dN6U'
				SLACK_WEBHOOK='https://hooks.slack.com/services/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'
				SLACK_CHANNEL='#redshop-builds'
			}
			parallel {
				stage('category') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'category'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Category"
						}
					}
				}
				stage('configuration') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'vistflow'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Configuration"
						}
					}
				}
				stage('Country') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'item'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Country"
						}
					}
				}
				stage('Coupon') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'coupon'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Coupon"
						}
					}
				}
				stage('Currency') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'currency'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Currency"
						}
					}
				}
				stage('Custom_Field') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'custom_field'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Custom_Field"
						}
					}
				}
				stage('Discount') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'discount'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/integration/Discount"
						}
					}
				}
				stage('Discount_Product') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'discount_product'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Discount_Product"
						}
					}
				}
				stage('Field_Group') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'intitem'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Field_Group"
						}
					}
				}
			}
			post {
				always {
					cleanWs()
					ws(pwd() + "@tmp") {
						cleanWs()
					}
				}
			}
		}
		stage('Automated Tests - Batch 2/3') {
			environment {
				GITHUB_TOKEN='4d92f9e8be0eddc0e54445ff45bf1ca5a846b609'
				GITHUB_REPO='redCOMPONENT-COM/redshop'
				CLOUDINARY_CLOUD_NAME='redcomponent'
				CLOUDINARY_API_KEY='365447364384436'
				CLOUDINARY_API_SECRET='Q94UM5kjZkZIrau8MIL93m0dN6U'
				SLACK_WEBHOOK='https://hooks.slack.com/services/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'
				SLACK_CHANNEL='#redshop-builds'
			}
			parallel {
				stage('Giftcard') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'giftcard'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Giftcard"
						}
					}
				}
				stage('Mail') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'mail'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Mail"
						}
					}
				}
				stage('Manufacture') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'ctamc'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry (1) {
							sh "build/system-tests.sh acceptance/administrator/Manufacturer"
						}
					}
				}
				stage('Mass_Discount') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'Mass_Discount'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Mass_Discount"
						}
					}
				}
				stage('Notices') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'Notices'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Notices"
						}
					}
				}
				stage('Order') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'order'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Order"
						}
					}
				}
				stage('Price_Product') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'Price_Product'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Price_Product"
						}
					}
				}
				stage('Product') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'Product'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Product"
						}
					}
				}
				stage('Quotation') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'quotation'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Quotation"
						}
					}
				}
				stage('Shipping') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'Shipping'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Shipping"
						}
					}
				}
				stage('Shopper_Group') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'Shopper_Group'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Shopper_Group"
						}
					}
				}
				stage('State') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'State'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/State"
						}
					}
				}
				stage('Stock_Image') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'Stock_Image'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/administrator/Stock_Image"
						}
					}
				}
			}
			post {
				always {
					cleanWs()
					ws(pwd() + "@tmp") {
						cleanWs()
					}
				}
			}
		}
		stage('Automated Tests - Batch 3/3') {
			environment {
				GITHUB_TOKEN='4d92f9e8be0eddc0e54445ff45bf1ca5a846b609'
				GITHUB_REPO='redCOMPONENT-COM/redshop'
				CLOUDINARY_CLOUD_NAME='redcomponent'
				CLOUDINARY_API_KEY='365447364384436'
				CLOUDINARY_API_SECRET='Q94UM5kjZkZIrau8MIL93m0dN6U'
				SLACK_WEBHOOK='https://hooks.slack.com/services/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'
				SLACK_CHANNEL='#redshop-builds'
			}
			parallel {
				stage('Product_Attribute') {
					agent {
						docker {
							image 'jatitoam/docker-systemtests'
							args  "--network tn-${BUILD_TAG} --user 0 --privileged=true"
						}
					}
					steps {
						script {
							env.STAGE = 'Product_Attribute'
						}
						unstash 'vendor'
						unstash 'joomla-cms'
						unstash 'chromeD'
						unstash 'redshop'
						unstash 'database-dump'
						retry(1) {
							sh "build/system-tests.sh acceptance/integration"
						}
					}
				}

			post {
				always {
					cleanWs()
					ws(pwd() + "@tmp") {
						cleanWs()
					}
				}
			}
		}
	}
	post {
		always {
			dockerDBRemove("db-${BUILD_TAG}");
			dockerNetworkRemove("tn-${BUILD_TAG}");
			dockerVolumeRemove();
			cleanWs()
			ws(pwd() + "@") {
				cleanWs()
			}
			wipeWorkspaces() // Always run this last
		}
	}
}

def dockerNetworkCreate(name) {
	dockerNetworkRemove(name)
	sh "docker network create ${name}"
}

def dockerNetworkRemove(name) {
	sh "docker network rm ${name} || true"
}

def dockerDBRun(hostname, password, network) {
	dockerDBRemove(hostname)
	sh "docker run --name ${hostname} --network ${network} -e MYSQL_ROOT_PASSWORD=${password} -d mysql:5.7"
}

def dockerDBRemove(hostname) {
	sh "docker stop ${hostname} || true"
	sh "docker rm ${hostname} || true"
}

def dockerVolumeRemove() {
	sh "docker volume prune -f"
}

def wipeWorkspaces()
{
	dir('/var/lib/jenkins/workspace'){
		sh 'pwd'
		sh 'find -maxdepth 1 -name $(basename ${WORKSPACE})@\\* ! -name $(basename ${WORKSPACE})@tmp'

		sh 'sudo rm -rf -- $(basename ${WORKSPACE})_*/'
		// Removes the clones & tmp folders created for parallelization
		sh 'find -maxdepth 1 -name $(basename ${WORKSPACE})@\\* ! -name $(basename ${WORKSPACE})@tmp -exec sudo rm -rf {} \\;'
	}

	// Wipes the main workspace afterwards leaving an empty dir
	deleteDir()
}
