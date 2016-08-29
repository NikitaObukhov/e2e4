angular
  .module('app')
  .component('mainSection', {
    templateUrl: 'app/components/MainSection.html',
    controller: MainSection,
    bindings: {
      todos: '=',
      filter: '<'
    }
  });

/** @ngInject */
function MainSection() {

}

MainSection.prototype = {
  handleClearCompleted: function () {

  },

  handleCompleteAll: function () {

  },

  handleShow: function (filter) {

  },

  handleChange: function (id) {

  },

  handleSave: function (e) {
    if (e.text.length === 0) {
      this.todos = this.todoService.deleteTodo(e.id, this.todos);
    } else {
      this.todos = this.todoService.editTodo(e.id, e.text, this.todos);
    }
  },

  handleDestroy: function (e) {
    this.todos = this.todoService.deleteTodo(e, this.todos);
  }
};
