import {Component, OnInit, Output, EventEmitter} from '@angular/core';

import {UserService} from '../../services/user.service';
import {AccountService} from '../../services/account.service';
import {User} from '../../models/user.model';
import {Account} from '../../models/account.model';
import {ErrorComponent} from '../error/error.component';

@Component({
    selector: 'add-account',
    templateUrl: './add-account.component.html',
    styleUrls: ['./add-account.component.css']
})
export class AddAccountComponent implements OnInit {
    @Output() public hideAddAccount = new EventEmitter<void>();
    @Output() public returnAccount = new EventEmitter<Account>();
    protected errors: String[];
    private user: User;
    private account_name: String;
    private account_amount: number;

    constructor(
        private userService: UserService,
        private accountService: AccountService
    ) {}

    public ngOnInit(): void {
        this.user = this.userService.getUser();
    }

    protected hide(): void {
        this.hideAddAccount.emit();
    }

    protected saveAccount(): void {
        this.errors = this.checkForErrors();

        if (this.errors.length) {
            return;
        }

        this.accountService.saveAccount(
            this.user.id,
            this.account_name,
            this.account_amount
        ).then(
            (response) => {
                this.addAccount(response.data);
                this.hide();
            }
        );
    }

    private checkForErrors(): String[] {
        let errors = [];

        if (isNaN(this.account_amount)) {
            errors.push('Invalid Amount: Amount must be a number');
        }

        return errors;
    }

    private addAccount(newAccount: Account): void {
        this.returnAccount.emit(newAccount);
    }
}