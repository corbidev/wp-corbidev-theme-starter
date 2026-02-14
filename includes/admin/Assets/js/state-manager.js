window.CorbiDevStore = (function(){

    let state = {};
    let initialState = {};
    let listeners = [];

    function hydrate(data) {
        state = {...data};
        initialState = JSON.parse(JSON.stringify(data));
        notify();
    }

    function set(key, value) {
        state[key] = value;
        notify();
    }

    function get(key) {
        return state[key];
    }

    function isDirty() {
        return JSON.stringify(state) !== JSON.stringify(initialState);
    }

    function reset() {
        state = JSON.parse(JSON.stringify(initialState));
        notify();
    }

    function subscribe(fn) {
        listeners.push(fn);
    }

    function notify() {
        listeners.forEach(fn => fn(state));
    }

    return { hydrate, set, get, isDirty, reset, subscribe };

})();
