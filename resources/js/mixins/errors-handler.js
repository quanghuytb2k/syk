const errorsHandler = {
    data() {
        return {
            errorResponse: undefined,
        }
    },
    methods: {
        tryGetErrorResponse(error) {
            try {
                this.errorResponse = error.response.data;
                return true;
            } catch {
                return false;
            }
        },
        isInvalid(key) {
            try {
                return this.errorResponse.errors[key] !== undefined;
            } catch {
                return false;
            }
        },
        invalidMessages(key) {
            try {
                return this.errorResponse.errors[key];
            } catch {
                return null;
            }
        },
        hasError() {
            return this.errorResponse !== undefined;
        },
        errorMessage() {
            try {
                return this.errorResponse.message;
            } catch {
                return null;
            }
        },
        clearError() {
            this.errorResponse = undefined;
        },
    }
}

export default errorsHandler
