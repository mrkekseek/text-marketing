

<div style="margin: 0 auto; min-width: 320px; background: #EAEAEA;">
	<div style="text-align: center; background: #fff;">
		<div style="background: #fff; max-width: 700px; display: inline-block;">
			<div>
				<h1 style="margin-bottom: 0; color: #C82027">{{ $company_name }}</h1>
			</div>
			<div class="outer-box" style="padding: 30px; text-align: left;">
				<p style="text-align: center;">
				</p>
				<table class="inner" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; margin: 20px 0;">
					<tr>
						<td style="border: 0; vertical-align: middle; font: 13px/1.3 arial, sans-serif; color: #6E6E6E; text-align: right; padding-right: 2%;">
							&nbsp;
						</td>
						<td class="star" style="border: 0; vertical-align: middle; width: 12%; text-align: center;">
							<a href="{{config('app.url')}}/seances/{{ $id }}/1" style="text-decoration: none; opacity: 0.2;">
								<img src="{{config('app.url')}}/img/star_hover.png" alt="1" style="width: 100%; vertical-align: middle;" />
							</a>
						</td>
						<td class="star" style="border: 0; vertical-align: middle; width: 12%; text-align: center;">
							<a href="{{config('app.url')}}/seances/{{ $id }}/2" style="text-decoration: none; opacity: 0.4;">
								<img src="{{config('app.url')}}/img/star_hover.png" alt="2" style="width: 100%; vertical-align: middle;" />
							</a>
						</td>
						<td class="star" style="border: 0; vertical-align: middle; width: 12%; text-align: center;">
							<a href="{{config('app.url')}}/seances/{{ $id }}/3" style="text-decoration: none; opacity: 0.6;">
								<img src="{{config('app.url')}}/img/star_hover.png" alt="3" style="width: 100%; vertical-align: middle;" />
							</a>
						</td>
						<td class="star" style="border: 0; vertical-align: middle; width: 12%; text-align: center;">
							<a href="{{config('app.url')}}/seances/{{ $id }}/4" style="text-decoration: none; opacity: 0.8;">
								<img src="{{config('app.url')}}/img/star_hover.png" alt="4" style="width: 100%; vertical-align: middle;" />
							</a>
						</td>
						<td class="star" style="border: 0; vertical-align: middle; width: 12%; text-align: center;">
							<a href="{{config('app.url')}}/seances/{{ $id }}/5" style="text-decoration: none; opacity: 1.0;">
								<img src="{{config('app.url')}}/img/star_hover.png" alt="5" style="width: 100%; vertical-align: middle;" />
							</a>
						</td>
						<td style="border: 0; vertical-align: middle; font: 13px/1.3 arial, sans-serif; color: #6E6E6E; text-align: left; padding-left: 2%;">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td style="border: 0; padding-top: 10px; font: 13px/1.3 arial, sans-serif; color: #6E6E6E; text-align: right; padding-right: 2%;">
						</td>

						<td style="border: 0; width: 12%; font: 13px/1.3 arial, sans-serif; color: #6E6E6E; padding-top: 10px; text-align: center;">
							1
						</td>
						<td style="border: 0; width: 12%; font: 13px/1.3 arial, sans-serif; color: #6E6E6E; padding-top: 10px; text-align: center;">
							2
						</td>
						<td style="border: 0; width: 12%; font: 13px/1.3 arial, sans-serif; color: #6E6E6E; padding-top: 10px; text-align: center;">
							3
						</td>
						<td style="border: 0; width: 12%; font: 13px/1.3 arial, sans-serif; color: #6E6E6E; padding-top: 10px; text-align: center;">
							4
						</td>
						<td style="border: 0; width: 12%; font: 13px/1.3 arial, sans-serif; color: #6E6E6E; padding-top: 10px; text-align: center;">
							5
						</td>
						<td style="border: 0; padding-top: 10px; font: 13px/1.3 arial, sans-serif; color: #6E6E6E; text-align: left; padding-left: 2%;">
						</td>
					</tr>
				</table>
				<p style="text-align: center;">{{ $text }}</p>
			</div>
		</div>
		<div style="clear: both;">
		</div>
	</div>
</div>
<table style="width: 100%; color: #74787e;" class="footer" align="center" width="370" cellpadding="0" cellspacing="0">
    <tr style="background-color: #f5f8fa;">
        <td class="content-cell" align="center" id="footer" style="padding: 10px;">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </td>
    </tr>
</table>