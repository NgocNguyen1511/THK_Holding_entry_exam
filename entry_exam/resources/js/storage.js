const storage = {
    get(key, defaultValue = null) {
        const item = localStorage.getItem(key);
        if (item === null || item === undefined) {
            return defaultValue;
        }
        return JSON.parse(item);
    },

    set(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
    },

    remove(key) {
        localStorage.removeItem(key);
    },
};

export { storage };
