pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        sh 'composer install'
      }
    }
    stage('Test') {
      steps {
        sh 'mysql -u jenkins -pcakephp_jenkins -e \'DROP DATABASE IF EXISTS jenkins_test; CREATE DATABASE jenkins_test\';'
        sh 'cp config/app_local.php.template config/app_local.php'
        sh 'sed -i "s/{{mysql_test_user}}/jenkins/"             config/app_local.php'
        sh 'sed -i "s/{{mysql_test_password}}/cakephp_jenkins/" config/app_local.php'
        sh 'sed -i "s/{{mysql_test_db_name}}/jenkins_test/"     config/app_local.php'
        sh 'vendor/bin/phpunit'
      }
    }
  }
}
