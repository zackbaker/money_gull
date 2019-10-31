import {Component} from '@angular/core';
import {Router} from '@angular/router';

import {UserService} from '../../shared/services/user.service';
import {ErrorComponent} from '../../shared/components/error/error.component';

@Component({
    selector: 'signin-form',
    templateUrl: './signin-form.component.html',
    // styleUrls: ['./signin-from.component.css']
})
export class SigninFormComponent {
    public email;
    public password;
    public errors;

    constructor(
        private userService: UserService,
        private router: Router
    ){}

    submitForm() {
        this.resetError();

        let ret = this.userService.signInCall(this.email, this.password);
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