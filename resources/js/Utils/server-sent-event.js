/**
 * Usage @example :
 * const sse = new SSE({'X-CSRF-TOKEN':'eyJ'});
 *
 * sse.post('/api/chat', {prompt: 'Hello, world!', contextFilter: []})
 * .on('data', (data) => {})
 * .on('error', (error) => {})
 * .on('end', () => {});
 */
export default class SSE {
    constructor(headers = {}) {
        this.xhr = new XMLHttpRequest();

        this.headers = headers;

        this.eventListeners = {
            data: [],
            error: [],
            end: [],
        };
    }

    on(event, listener) {
        if (this.eventListeners[event]) {
            this.eventListeners[event].push(listener);
        }
        return this;
    }

    emit(event, data) {
        this.eventListeners[event].forEach((listener) => listener(data));
    }

    post(url, data) {
        this.xhr.open("POST", url, true);

        for (const [key, value] of Object.entries(this.headers)) {
            this.xhr.setRequestHeader(key, value);
        }

        // default headers
        this.xhr.setRequestHeader("Content-Type", "application/json");
        this.xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        this.lastProcessedLine = 0;

        this.xhr.onprogress = (event) => {
            const data = this.xhr.responseText;
            const lines = data.split("\n");

            // Process only new lines
            for (let i = this.lastProcessedLine; i < lines.length; i++) {
                const line = lines[i];

                if (line === "") {
                    continue;
                }

                const [event, message] = line.split(":");
                if (event === "data") {
                    this.emit("data", message);
                } else if (event === "error") {
                    this.emit("error", message);
                } else if (event === "end") {
                    this.emit("end", message);
                }
            }

            // Update the index of the last processed line
            this.lastProcessedLine = lines.length;
        };

        this.xhr.onerror = (event) => {
            this.emit("error", "An error occurred while sending the request.");
        };

        this.xhr.onabort = (event) => {
            this.emit("end", "The request has been aborted by the user.");
        };

        this.xhr.onload = (event) => {
            this.emit("end", "The request has been completed.");
        };

        this.xhr.send(JSON.stringify(data));
        return this;
    }

    close() {
        this.xhr.abort();
    }
}
