/* Local Storage Class */

function mMLocalStorage(key) {
    this.key = key || 'mobileMule.settings';

    mMLocalStorage.prototype._init();
}

mMLocalStorage.prototype._init = function() {
    if (!('localStorage' in window && window['localStorage'] !== null)) {
        throw new Error('No LocalStorage supported on this browser!');
    }
}

mMLocalStorage.prototype.set = function(name, value) {
    var settings = JSON.parse(localStorage.getItem(this.key)) || {};

    settings[name] = value;
    localStorage.setItem(this.key, JSON.stringify(settings));
}

mMLocalStorage.prototype.get = function(name, defaultValue) {
    defaultValue = defaultValue || undefined;

    var settings = JSON.parse(localStorage.getItem(this.key)) || {};

    return name in settings ? settings[name] : defaultValue;
}

mMLocalStorage.prototype.unset = function(name) {
    var settings = JSON.parse(localStorage.getItem(this.key));

    if (name in settings) {
        delete settings[name];
        localStorage.setItem(this.key, JSON.stringify(settings));
    }
}

mMLocalStorage.prototype.erase = function() {
    localStorage.removeItem(this.key);
}