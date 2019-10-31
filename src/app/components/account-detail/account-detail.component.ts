import {Component, OnInit} from '@angular/core';
import {Router, ActivatedRoute, ParamMap} from '@angular/router';

import {AccountService} from '../../shared/services/account.service';
import {TransactionService} from '../../shared/services/transaction.service';
import {UserService} from '../../shared/services/user.service';
import {User} from '../../shared/models/user.model';
import {Account} from '../../shared/models/account.model';
import {Transaction} from '../../shared/models/transaction.model';

@Component({
    selector: 'account-detail',
    templateUrl: './account-detail.component.html',
    styleUrls: ['./account-detail.component.css']
})
export class AccountDetailComponent implements OnInit {
    private user: User;
    private account: Account;
    private transactions: Transaction[];
    private account_ready: Boolean = false;

    constructor(
        private route: ActivatedRoute,
        private userService: UserService,
        private accountService: AccountService,
        private transactionService: TransactionService,
    ) {}

    ngOnInit(): void {
        let id = this.route.snapshot.paramMap.get('id');
        this.user = this.userService.getUser();
        this.accountService.getAccount(this.user.id, id)
            .then(
                (account) => {
                    this.account = account.data;
                    this.account_ready = true;
                }
            );
    }
}