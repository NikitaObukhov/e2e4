angular
  .module('app')
  .config(routesConfig);

/** @ngInject */
function routesConfig($stateProvider, $urlRouterProvider, $locationProvider, $transitionsProvider) {
  $locationProvider.html5Mode(true).hashPrefix('!');
  $urlRouterProvider.otherwise('^/search');

  $urlRouterProvider.when('/', '/search');
  $stateProvider
    .state('app', {
      url: '/',
      component: 'app'
    });
  $stateProvider
    .state('app.searchPage', {
      url: '^/search',
      component: 'searchPage'
    });
  $stateProvider
    .state('app.searchHistory', {
      url: '^/history',
      component: 'searchHistory'
    });
  $stateProvider
    .state('app.searchResults', {
      url: 'history/:id',
      component: 'searchResults'
    });
  $transitionsProvider.onBefore({ to: function(state) {
    console.debug(state);
  }, from: '*' }, function() {

  });
  $transitionsProvider.onStart({
    to: function(state) {
      console.debug(state);
      return state.redirectTo;
    }
  }, function($transition$, $state) {
    console.debug('HERE!~');
    return $state.redirect($transition$.to.redirectTo); });
  $transitionsProvider.onError({ }, function($error$) {
    console.debug($error$);
  });
}
