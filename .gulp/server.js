const { create } = require("browser-sync");
const sync = create();

const reload = done => {
    sync.reload();
    done();
};

const server = () => {
    sync.init({
        proxy: "test-mc-group/",
        ui: false,
        notify: false
    });
};

module.exports = {
    sync,
    server,
    reload
};
