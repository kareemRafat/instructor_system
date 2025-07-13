<?php

function renderEmailTemplate($salaryDataToEmail)
{
  ob_start(); ?>

  <!DOCTYPE html>
  <html lang="ar" dir="rtl">

  <head>
    <meta charset="UTF-8" />
    <title>ملخص الراتب</title>
  </head>

  <body style="margin:0;padding:0;background-color:#f0f0f0;font-family:'Tahoma','Arial',sans-serif;direction:rtl;">

    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:30px auto;background-color:#ffffff;border:1px solid #ddd;border-radius:8px;overflow:hidden;">

      <!-- Header -->
      <tr>
        <td style="background-color:#0d47a1;color:#fff;text-align:center;padding:20px;font-size:20px;font-weight:bold;">
          ملخص الراتب الشهري
        </td>
      </tr>

      <!-- Content -->
      <tr>
        <td style="padding:24px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;line-height:1.8;color:#222;">

            <!-- Row: Name & Date -->
            <tr>
              <td style="padding:8px 0;">
                <strong>اسم الموظف:</strong><br>
                <span style="color:blue;font-weight:bold"><?= $salaryDataToEmail['username'] ?></span>
              </td>
              <td style="padding:8px 0;text-align:left;">
                <strong>شهر المحاسبة :</strong><br>
                <span style="color:Brown;font-weight:bold"><?= $salaryDataToEmail['month'] ?></span>
              </td>
            </tr>

            <!-- Row: Basic & Overtime -->
            <tr style="background-color:#f9f9f9;">
              <td style="padding:10px 0;">
                <strong>الراتب الأساسي:</strong><br>
                <span style="color:Green;font-weight:bold"><?= $salaryDataToEmail['basic_salary'] ?></span> جنيه
              </td>
              <td style="padding:10px 0;text-align:left;">
                <strong>أيام الأوفر تايم:</strong><br>
                <span style="color:blue;font-weight:bold"><?= $salaryDataToEmail['overtime_days'] ?></span> أيام
              </td>
            </tr>

            <!-- Row: Day Value & Target -->
            <tr>
              <td style="padding:10px 0;">
                <strong>المكافآت:</strong><br>
                <span style="color:blue;font-weight:bold"><?= $salaryDataToEmail['bonuses'] ?></span> جنيه
              </td>
              <td style="padding:10px 0;text-align:left;">
                <strong>التارجت:</strong><br>
                <span style="color:blue;font-weight:bold"><?= $salaryDataToEmail['target'] ?></span> جنيه
              </td>
            </tr>

            <!-- Row: Bonuses & Advances -->
            <tr style="background-color:#f9f9f9;">
              <td style="padding:10px 0;">
                <strong>الغياب:</strong><br>
                <span style="color:blue;font-weight:bold"><?= $salaryDataToEmail['absent_days'] ?></span> أيام
              </td>
              <td style="padding:10px 0;text-align:left;">
                <strong>السلف:</strong><br>
                <span style="color:blue;font-weight:bold"><?= $salaryDataToEmail['advances'] ?></span> جنيه
              </td>

            </tr>

            <!-- Row: Absent & Deductions -->
            <tr>
              <td style="padding:10px 0;text-align:right;">
                <strong>الخصم:</strong><br>
                <span style="color:blue;font-weight:bold"><?= $salaryDataToEmail['deduction_days'] ?></span> أيام
              </td>
              <td style="padding:10px 0;text-align:left;">

              </td>
            </tr>

            <!-- Row: Total -->
            <tr>
              <td colspan="2" style="padding:20px 0;text-align:center;border-top:1px solid #ccc;">
                <strong style="font-size:18px;color:#1b5e20;">الإجمالي: <span><?= $salaryDataToEmail['total'] ?></span> جنيه</strong>
              </td>
            </tr>

          </table>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="text-align:center;padding:12px;font-size:13px;color:#777;background-color:#f1f1f1;">
          هذا البريد مُرسل تلقائيًا - لا تقم بالرد عليه.
        </td>
      </tr>

    </table>

  </body>

  </html>
<?php
  return ob_get_clean();
}
