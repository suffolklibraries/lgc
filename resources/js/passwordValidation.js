function PasswordValidation() {
    document.addEventListener('alpine:init', () => {
        Alpine.data('password', () => ({
            password: '',
            showInput: false,
            errors: [],
            rules: [
                {
                    key: 'minLength',
                    description: '8 characters minimum',
                },
                {
                    key: 'hasNumber',
                    description: 'One number',
                },
                {
                    key: 'containsUpper',
                    description: 'One uppercase letter',
                },
                {
                    key: 'containsSpecial',
                    description: 'One special character !@#$%^&*',
                }
            ],
            handleValidation() {
                this.errors = []

                if(!this.containsSpecial()) {
                    this.errors = [...this.errors, "containsSpecial"]
                }

                if(!this.containsUpper()) {
                    this.errors = [...this.errors, "containsUpper"]
                }

                if(!this.hasNumber()) {
                    this.errors = [...this.errors, "hasNumber"]
                }

                if(!this.minLength()) {
                    this.errors = [...this.errors, "minLength"]
                }

            },
            minLength() {
                return this.password.length >= 8
            },
            containsUpper() {
                return /[A-Z]/.test(this.password)
            },
            hasNumber() {
                return /[0-9]/.test(this.password)
            },
            containsSpecial() {
                return /[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/.test(this.password)
            },
            isValid(key) {
                return !this.errors?.includes(key)
            },
            init() {
                this.handleValidation()

                this.$watch('password', () => {
                    this.handleValidation();
                });
            }

        }))
    })
}


export default PasswordValidation;
