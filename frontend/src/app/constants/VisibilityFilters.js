function showAll() {
  return true;
}

function showCompleted(todo) {
  return todo.completed;
}

function showActive(todo) {
  return !todo.completed;
}

var visibilityFilters = {
};
