Feature: Export currencies
  In order to be able to access and modify currencies data outside PIM
  As an administrator
  I need to be able to export currencies

  @javascript
  Scenario: Successfully export currencies
    Given a "footwear" catalog configuration
    And the following job "csv_footwear_currency_export" configuration:
      | filePath | %tmp%/currency_export/currency_export.csv |
    And I am logged in as "Julia"
    And I am on the "csv_footwear_currency_export" export job page
    When I launch the export job
    And I wait for the "csv_footwear_currency_export" job to finish
    Then I should see "Read 101"
    And I should see "Written 101"
    And exported file of "csv_footwear_currency_export" should contain:
    """
    code;activated
    USD;1
    EUR;1
    ADP;0
    AED;0
    AFA;0
    ALK;0
    AOK;0
    AON;0
    AOR;0
    ARM;1
    ARP;0
    ARL;0
    ATS;0
    AZM;0
    BAD;0
    BAN;0
    BEC;0
    BEF;0
    BEL;0
    BGL;0
    BGM;0
    BGO;0
    BOL;0
    BOP;0
    BRB;0
    BRC;0
    BRE;0
    BRR;0
    BRN;0
    BRZ;0
    BYB;0
    CHE;0
    CHW;0
    CSD;0
    CSK;0
    DEM;0
    EEK;0
    ESA;0
    ESB;0
    ESP;0
    FIM;0
    FRF;0
    GHC;0
    GBP;0
    GRD;0
    GYD;0
    IEP;0
    ISJ;0
    ILR;0
    ITL;0
    KRH;0
    KRO;0
    LUF;0
    LVR;0
    MGF;0
    MKN;0
    MTL;0
    MXP;0
    MZE;0
    MZM;0
    NIC;0
    NLG;0
    PES;0
    PLZ;0
    PTE;0
    ROL;0
    SDD;0
    SDP;0
    SIT;0
    SKK;0
    SUR;0
    TMM;0
    TPE;0
    TRL;0
    UGS;0
    UYP;0
    VEB;0
    VNN;0
    XAU;0
    XBA;0
    XBB;0
    XBC;0
    XBD;0
    XEU;0
    XFO;0
    XFU;0
    XPD;0
    XDR;0
    XSU;0
    XTS;0
    XXX;0
    YUD;0
    YUM;0
    YUN;0
    YUR;0
    ZMK;0
    ZRN;0
    ZRZ;0
    ZWD;0
    ZWL;0
    ZWR;0
    """
