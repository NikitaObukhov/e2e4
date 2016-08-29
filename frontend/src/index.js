angular
  .module('app', ['ui.router', 'ui.bootstrap', 'formly', 'formlyBootstrap', 'ngMessages', 'ngTable', 'ngSanitize', 'core', 'search'])
  .config(function(ConfigProvider) {
    ConfigProvider.set('Backend.baseUrl', "${ENV_BACKEND_BASE_URL}");
    ConfigProvider.set('Backend.basePath', '${ENV_BACKEND_BASE_PATH}');
  })
  .run(function($rootScope, $state, $stateParams) {
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
    console.debug('RUN');
    $rootScope.$on('$stateChangeStart', function (event, next, toParams, fromState, fromParams) {
      console.log('STATE START>', next);
    });
    $rootScope.$on('$stateNotFound', function (event, unfoundState, fromState, fromParams) {
      console.log('STATE NOT FOUND>', unfoundState);
    });
    $rootScope.$on('$stateChangeError', function (event, next, toParams, fromState, fromParams, error) {
      console.log('STATE ERROR>', next, error);
    });
    $rootScope.$on('$stateChangeSuccess', function (event, next, fromState, fromParams) {
      console.log('STATE SUCCESS>', next);
    });
  });