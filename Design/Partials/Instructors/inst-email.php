<?php

function renderGroupFinishEmail($data)
{
  ob_start(); ?>

  <!DOCTYPE html>
  <html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <title>انتهاء المجموعة</title>
  </head>

  <body style="margin:0;padding:0;background-color:#f0f0f0;font-family:'Tahoma','Arial',sans-serif;direction:rtl;font-size:13px;">

    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:30px auto;background-color:#ffffff;border:1px solid #ddd;border-radius:8px;overflow:hidden;">

      <!-- Header -->
      <tr>
        <td style="background-color:#2e7d32;color:#fff;text-align:center;padding:16px;font-size:16px;font-weight:bold;">
          إشعار بانتهاء المجموعة
        </td>
      </tr>

      <!-- Content -->
      <tr>
        <td style="padding:20px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.7;color:#222;">

            <!-- Group Info -->
            <tr style="background-color:#f9f9f9;">
              <td style="padding:6px 0;">
                <strong>اسم المجموعة:</strong><br>
                <span style="font-weight:bold"><?= $data['group_name'] ?></span>
              </td>
            </tr>

            <!-- End Date -->
            <tr>
              <td style="padding:6px 0;">
                <strong>تاريخ انتهاء المجموعة:</strong><br>
                <span style="color:brown;font-weight:bold"><?= date('d-m-Y', strtotime($data['end_date'])) ?></span>

              </td>
            </tr>

            <!-- Bonus Info -->
            <tr style="background-color:#f9f9f9;">
              <td style="padding:6px 0;">
                <strong>هل تم صرف مكافأة؟</strong><br>
                <span style="font-weight:bold;color:<?= $data['has_bonus'] ? 'green' : 'red' ?>">
                  <?= $data['has_bonus'] ? 'نعم - تم صرف مكافأة بقيمة ' . $data['bonus_amount'] . ' جنيه' : 'لا توجد مكافأة لهذه المجموعة' ?>
                </span>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="text-align:center;padding:10px;font-size:13px;color:#777;background-color:#f1f1f1;">
          هذا البريد مُرسل تلقائيًا - لا تقم بالرد عليه.
        </td>
      </tr>

    </table>

  </body>
  </html>

<?php
  return ob_get_clean();
}
