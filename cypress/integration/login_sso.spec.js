describe('Login form', () => {
  const URL_ROOT = 'http://ssomoc.localhost/';
  it('should allow the user login with sso button.', () => {
    cy.visit(URL_ROOT);

    cy.get('#buttonSso').click();
    cy.location('pathname').should('include', 'profile');

    cy.get('#linkLogout').click();
    cy.location('pathname').should('include', '');
  });
});
