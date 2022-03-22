<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Anggota;
use App\Models\Transaksi;
use App\Models\Pembiayaan\PmbTunai;
use DB;

use App\Helpers\SimpananHelp;
class MenuHelp
{

    public static function mainMenu()
    {
        $notif_wajib = SimpananHelp::getTransactionPending('simkop');
        $simla_deposit = SimpananHelp::getTransactionPending('simla deposit');
        $simla_withdraw = SimpananHelp::getTransactionPending('simla withdraw');
        $menuList = [
            [
                "icon" => "si si-speedometer",
                "name" => "Dashboard",
                "to" => "dashboard",
            ],
            [
                "name" => 'Anggota',
                "icon" => 'si si-user',
                "subActivePaths" => 'anggota.*',
                "sub" => [
                    [
                      "name" => 'Tambah Anggota Baru',
                      "to" => 'anggota.create',
                    ],
                    [
                      "name" => 'List Anggota',
                      "to" => 'anggota.index',
                    ],
                ]
            ],
            [
              "name" => 'Simpanan',
              "icon" => 'si si-wallet',
              "subActivePaths" => 'simpanan.*',
              "sub" => [
                  [
                    "name" => 'Wajib',
                    "subActivePaths" => 'simpanan.wajib.*',
                    "badge"  => $notif_wajib,
                    "sub" => [
                        [
                          "name" => 'Setoran',
                          "to" => 'simpanan.wajib.index',
                          "subActivePaths" => 'simpanan.wajib*',
                          "badge"  => $notif_wajib
                        ],
                        [
                          "name" => 'Tunggakan',
                          "to" => 'simpanan.wajib.tunggakan',
                        ],
                    ]
                  ],
                  [
                    "name" => 'Sukarela',
                    "subActivePaths" => 'simpanan.sukarela.*',
                    "badge"  => $simla_deposit + $simla_withdraw,
                    "sub" => [
                        [
                          "name" => 'Setoran',
                          "to" => 'simpanan.sukarela.index',
                          "subActivePaths" => 'simpanan.sukarela.deposit*',
                          "badge"  => $simla_deposit,
                        ],
                        [
                          "name" => 'Penarikan',
                          "to" => 'simpanan.sukarela.withdraw.index',
                          "subActivePaths" => 'simpanan.sukarela.withdraw.deposit*',
                          "badge"  => $simla_withdraw,
                        ],
                    ]
                  ],
              ]
            ],
            [
              "name" => 'Akunting',
              "icon" => 'si si-calculator',
              "subActivePaths" => 'accounting.*',
              "sub" => [
                [
                    "name" => 'Piutang',
                    "to" => 'accounting.potong_gaji.index',
                ],
                [
                    "name" => 'Kas',
                    "to" => 'accounting.cash.index',
                ],
                [
                  "name" => 'Konfigurasi',
                  "subActivePaths" => 'accounting.config.*',
                  "sub" => [
                    [
                        "name" => 'Metode Pembayaran',
                        "to" => 'accounting.config.payment_method.index',
                    ],
                    [
                        "name" => 'Bank',
                        "to" => 'accounting.config.bank.index',
                    ],
                    [
                        "name" => 'Jurnal Akun',
                        "to" => 'accounting.config.account.index',
                    ],
                  ]
                ],
                [
                  "name" => 'Reports',
                  "subActivePaths" => 'accounting.report.*',
                  "sub" => [
                    [
                        "name" => 'Simpanan',
                        "to" => 'accounting.report.simpanan',
                    ],
                    [
                        "name" => 'Neraca Saldo',
                        "to" => 'accounting.report.balance_sheet',
                    ],
                  ]
                ],
              ]
            ],
            [
              "name" => 'Pengaturan',
              "icon" => 'si si-wrench',
              "subActivePaths" => 'settings.*',
              "sub" => [
                  [
                    "name" => 'Staff',
                    "to" => 'settings.staff.index',
                  ],
                  [
                    "name" => 'Staff Role',
                    "to" => 'settings.roles.index',
                  ],
                  [
                    "name" => 'General',
                    "to" => 'settings.general.index',
                  ]
                ],
            ]
        ];

        $data = Collect($menuList);

        return $data;
    }
}
