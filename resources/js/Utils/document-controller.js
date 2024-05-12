export default class DocumentController {
    static selectedDocuments = [];

    static getSelectedDocuments() {
        return this.selectedDocuments;
    }

    static addSelectedDocument(document) {
        this.selectedDocuments.push(document);
    }

    static removeSelectedDocument(document) {
        this.selectedDocuments = this.selectedDocuments.filter((item) => item.id !== document.id);
    }
}
