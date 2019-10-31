import { MoneyGullPage } from './app.po';

describe('money-gull App', () => {
  let page: MoneyGullPage;

  beforeEach(() => {
    page = new MoneyGullPage();
  });

  it('should display welcome message', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('Welcome to app!');
  });
});
