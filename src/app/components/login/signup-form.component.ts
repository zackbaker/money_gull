import {Component} from '@angular/core';
import {Router} from '@angular/router';

import {UserService} from '../../shared/services/user.service';
import {ErrorComponent} from '../../shared/components/error/error.component';

@Component({
  selector: 'signup-form',
  templateUrl: './signup-form.component.html',
  // styleUrls: ['./signup-from.component.css']
})
export class SignupFormComponent {
    public username;
    public email;
    public password;
    public errors;

    constructor(
        private userService: UserService,
        private router: Router
    ){}

    submitForm() {
        this.resetError();

        let ret = this.userService.signUpCall(
            this.email,
            this.password,
            this.username
        );
        ret.then(
            (response) => {
                if (response.hasOwnProperty('error')) {
                    if (response.error.message instanceof Array) {
                        this.errors = response.error.message;
                    } else {
                        this.errors = [response.error.message];
                    }
                } else {
                    this.router.navigate(['/home']);
                }
            }
        );
    }

    private resetError(): void {
        this.errors = [];
    }
}