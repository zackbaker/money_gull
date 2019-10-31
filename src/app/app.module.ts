import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {FormsModule} from '@angular/forms';
import {HttpModule} from '@angular/http';

import {AppRoutingModule} from './app-routing.module';

// Views
import {AppComponent} from './app.component';
import {LoginComponent} from './components/login/login.component';
import {HomeComponent} from './components/home/home.component';
import {AccountsListComponent} from './components/accounts-list/accounts-list.component';
import {GoalsListComponent} from './components/goals-list/goals-list.component';
import {AccountDetailComponent} from './components/account-detail/account-detail.component';

// View helpers
import {SigninFormComponent} from './components/login/signin-form.component';
import {SignupFormComponent} from './components/login/signup-form.component';
import {GoalComponent} from './shared/components/goal/goal.component';
import {TransactionsComponent} from './shared/components/transactions/transactions.component';
import {AccountComponent} from './shared/components/account/account.component';
import {AddAccountComponent} from './shared/components/add-account/add-account.component';
import {AddGoalComponent} from './shared/components/add-goal/add-goal.component';
import {ErrorComponent} from './shared/components/error/error.component';

// Services
import {UserService} from './shared/services/user.service';
import {GoalService} from './shared/services/goal.service';
import {AccountService} from './shared/services/account.service';
import {TransactionService} from './shared/services/transaction.service';
import {DateTimeService} from './shared/services/date-time.service';

@NgModule({
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    AppRoutingModule
  ],
  declarations: [
    AppComponent,
    LoginComponent,
    SigninFormComponent,
    SignupFormComponent,
    HomeComponent,
    GoalComponent,
    TransactionsComponent,
    AccountsListComponent,
    AccountComponent,
    AddAccountComponent,
    ErrorComponent,
    GoalsListComponent,
    AddGoalComponent,
    AccountDetailComponent,
  ],
  providers: [
    UserService,
    GoalService,
    TransactionService,
    AccountService,
    DateTimeService,
  ],
  bootstrap: [AppComponent]
})
export class AppModule {}