# Team profiles

One file per member of Team Uncrown. Each file holds what the site shows for that member:

| Field | What it is |
|---|---|
| `name` | Full name (also used to match the member on the site) |
| `role` | Role shown on the Team card and profile |
| `order` | Position on the Team section (1 = first) |
| `short_bio` | Short text on the Team card |
| `description` | Full description on the member's profile page |
| `skills` | Skill tags |
| `skill_levels` | Skill name and level from 0 to 100, used for the skill bars and the Team skills chart |
| `photo` | File name of the member's photo in `wp-content/uploads/` |

Private emails are never stored here.

**Site → files:** WP Admin → Team Members → Team Profiles → **Download team profiles**, then unzip into this folder.
**Files → site:** edit these files, deploy the theme, then WP Admin → Team Members → Team Profiles → **Load profiles into Team Members**.

`example-member.json.sample` shows the format. It is ignored by the site; the real files end in `.json`.
