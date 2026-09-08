<?php

/*
  OpenSB: The Open SquareBracket Software

  Copyright (C) 2026 Chaziz

  OpenSB is free software: you can redistribute it and/or modify it under the 
  terms of the GNU Affero General Public License as published by the Free 
  Software Foundation, either version 3 of the License, or (at your option) any
  later version. 

  OpenSB is distributed in the hope that it will be useful, but WITHOUT ANY 
  WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
  FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more 
  details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

namespace Data\Upload;

enum UploadQueryTypeEnum: int
{
    // Default (end-user, hide taken down/shadow-banned uploads)
    case Default = 0;
    // My uploads (will be used in OpenSB 2.2)
    case MyUploads = 1;
    // User profile / collections (end-user, hide taken down but not shadow-banned uploads)
    case Profile = 2;
    // Dashboard (staff, show everything)
    case Dashboard = 3;
}