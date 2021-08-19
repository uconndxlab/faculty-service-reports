// 5110-5112 Permanent Employees
// 5230-5260 Temporary Salaries
// 5310-5390, 5392 Other Personal Services
// 5391, 5610-5670, 5800, 5810 Fringe Benefits

const code_lookup = {
    '5100': 'Personal Services - Budget Pool', // No actuals
    '5109': 'Pending Refill - Search', // No actuals
    '5110': 'Regular Payroll - Classified',
    '5111': 'Regular Payroll – Faculty',
    '5112': 'Regular Payroll – Other Professional',
    '5230': 'Payroll - Durational and Temporary',
    '5231': 'Payroll – Contractual',
    '5232': 'Payroll – Faculty Summer',
    '5233': 'Payroll - Adjunct Faculty',
    '5240': 'Payroll – Student Labor',
    '5245': 'CWS Payroll - Student Labor (CWS only)',
    '5250': 'Payroll – Graduates Students',
    '5260': 'Payroll – Post Doctoral Fellows',
    '5310': 'Payroll - Snow and Ice Differential',
    '5320': 'Payroll - Accrued Vacation',
    '5330': 'Payroll - Allowances',
    '5340': 'Payroll - Holiday Pay',
    '5350': 'Payroll - Lump Sum Payments',
    '5360': 'Payroll - Longevity',
    '5370': 'Payroll - Overtime',
    '5380': 'Payroll - Shift Differential',
    '5390': 'Payroll - Accrued Sick',
    '5392': 'Payroll - Long-Term Disability',

    // Fringe Benefits
    '5391': 'AAUP 1% Buyback',
    '5610': 'Fringe Benefits - Classified',
    '5611': 'Fringe Benefits - Faculty',
    '5612': 'Fringe Benefits - Other Professional',
    '5630': 'Fringe Benefits - Durational and Temporary',
    '5631': 'Fringe Benefits - Contractual',
    '5632': 'Fringe Benefits - Faculty Summery Pay',
    '5633': 'Fringe Benefits - Adjunct Faculty',
    '5640': 'Fringe Benefits - Student Labor',
    '5650': 'Fringe Benefits - Graduate Students',
    '5660': 'Fringe Benefits - Post Doctoral Fellows',
    '5670': 'Fringe Benefits - Other Personal Services',
    '5800': 'Pension Expense Adjustment',
    '5810': 'OPEB Expense Adjustment'
}

export const PAYROLL_CODE_LOOKUP = code_lookup

export function getPayrollLabelForCode (code) {
    if ( code in code_lookup ) {
        return code_lookup[code]
    }
    return '(No Payroll Label)'
}