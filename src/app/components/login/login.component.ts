import {Component} from '@angular/core';

import {SigninFormComponent} from './signin-form.component';
import {SignupFormComponent} from './signup-form.component';

@Component({
  selector: 'login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  private signin_visible = true;
  private signup_visible = false;

  constructor(){}

  showSignIn(): void {
    this.signup_visible = false;
    this.signin_visible = true;
  }

  showSignUp(): void {
    this.signin_visible = false;
    this.signup_visible = true;
  }
}