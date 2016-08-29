angular
  .module('app')
  .component('searchHistory', {
    templateUrl: 'app/components/SearchHistory.html',
    controller: searchHistory,
    bindings: {
      history: '=?'
    }
  })
  .filter('toDate', function() {
    return function(input) {
      return new Date(input);
    };
  });

function searchHistory($rootScope, NgTableParams, SearchRequestRepository) {
  var that = this;
  $rootScope.$on('parseForm.newResult', function(e, searchResult) {
    that.history = [searchResult];
    that.table.reload();
  });
  this.table = new NgTableParams({}, {
    counts: [30,40],
    getData: function(params) {
      if (that.history) {
        params.total(that.history.length);
        return that.history;
      }
      var promise = SearchRequestRepository.history({
        page: params.page(),
        'per-page': params.count()
      });
      promise.then(function(collection) {
        params.total(collection.prototype.total);
      });
      return promise;
    }
  });
}