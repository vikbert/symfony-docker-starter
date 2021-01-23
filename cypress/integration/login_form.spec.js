describe('Login form', () => {
  const URL_ROOT = 'http://ssomoc.localhost/';
  it('should allow the user login with email and password.', () => {
    cy.visit(URL_ROOT + 'login');

    cy.get('#inputEmail').type('ssomoc@mail.com');
    cy.get('#inputPassword').type('ssomoc');

    cy.get('form').submit();

    cy.location('pathname').should('include', 'profile');
    cy.contains('secured profile page');

    cy.get('#linkLogout').click();
    cy.location('pathname').should('include', '');
  });
});
