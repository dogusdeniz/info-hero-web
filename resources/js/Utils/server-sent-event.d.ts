// Path: resources/js/Utils/server-sent-event.js
export declare class SSE {
    constructor(headers?: object);
    on(event: string, listener: Function): this;
    emit(event: string, data: any): void;
    post(url: string, data: object): this;
    close(): void;
}
