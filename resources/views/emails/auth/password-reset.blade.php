<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset your password — SellSpree</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f4f5;padding:48px 20px;">
    <tr>
      <td align="center">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:520px;">

          <!-- Brand -->
          <tr>
            <td align="center" style="padding-bottom:28px;">
              <span style="font-size:22px;font-weight:800;color:#16a34a;letter-spacing:-0.5px;text-decoration:none;">SellSpree</span>
            </td>
          </tr>

          <!-- Card -->
          <tr>
            <td style="background:#ffffff;border-radius:16px;padding:44px 44px 36px;box-shadow:0 1px 6px rgba(0,0,0,0.06);">
              <table width="100%" cellpadding="0" cellspacing="0" border="0">

                <!-- Lock icon -->
                <tr>
                  <td align="center" style="padding-bottom:28px;">
                    <div style="width:60px;height:60px;background:#f0fdf4;border-radius:16px;display:inline-block;text-align:center;line-height:60px;">
                      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                        <path d="M12 1C9.24 1 7 3.24 7 6V8H5C3.9 8 3 8.9 3 10V20C3 21.1 3.9 22 5 22H19C20.1 22 21 21.1 21 20V10C21 8.9 20.1 8 19 8H17V6C17 3.24 14.76 1 12 1ZM12 3C13.66 3 15 4.34 15 6V8H9V6C9 4.34 10.34 3 12 3ZM12 17C10.9 17 10 16.1 10 15C10 13.9 10.9 13 12 13C13.1 13 14 13.9 14 15C14 16.1 13.1 17 12 17Z" fill="#16a34a"/>
                      </svg>
                    </div>
                  </td>
                </tr>

                <!-- Heading -->
                <tr>
                  <td align="center" style="padding-bottom:8px;">
                    <h1 style="margin:0;font-size:24px;font-weight:700;color:#111827;line-height:1.3;">Reset your password</h1>
                  </td>
                </tr>

                <!-- Subtext -->
                <tr>
                  <td align="center" style="padding-bottom:36px;">
                    <p style="margin:0;font-size:15px;color:#6b7280;line-height:1.7;max-width:360px;">
                      Hi <strong style="color:#111827;">{{ $name }}</strong>, we received a request to reset your
                      <strong style="color:#111827;">{{ $portal }}</strong> account password.
                      Use the code below — it expires in <strong style="color:#111827;">15 minutes</strong>.
                    </p>
                  </td>
                </tr>

                <!-- Code box -->
                <tr>
                  <td align="center" style="padding-bottom:36px;">
                    <table cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td style="background:#f0fdf4;border:2px dashed #86efac;border-radius:14px;padding:22px 48px;text-align:center;">
                          <span style="font-size:44px;font-weight:800;letter-spacing:14px;color:#16a34a;font-family:'Courier New',Courier,monospace;display:inline-block;padding-right:-14px;">{{ $code }}</span>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <!-- Divider -->
                <tr>
                  <td style="padding-bottom:24px;">
                    <hr style="border:none;border-top:1px solid #f3f4f6;margin:0;" />
                  </td>
                </tr>

                <!-- Disclaimer -->
                <tr>
                  <td align="center">
                    <p style="margin:0;font-size:13px;color:#9ca3af;line-height:1.7;">
                      If you didn't request a password reset, you can safely ignore this email.<br />
                      Your password will not change until you use this code.
                    </p>
                  </td>
                </tr>

              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="padding-top:28px;">
              <p style="margin:0;font-size:12px;color:#9ca3af;line-height:1.6;">
                © {{ date('Y') }} SellSpree. All rights reserved.<br />
                This is an automated message — please do not reply.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
